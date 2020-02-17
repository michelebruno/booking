import React, { useState , useEffect, useCallback } from 'react'
import localization from './localization';
import PreLoaderWidget from '../components/Loader';
import Tooltip  from "@material-ui/core/Tooltip"
import { IconButton } from '@material-ui/core';

/**
 * 
 * @param {string} baseUrl L'url da cui prendere la collezione
 * @param {object} defaultFilter 
 * @returns { Array[ collection , filter,  setFilter , datatableOptions , reload ]}
 */
export default function useServerSideCollection( baseUrl , defaultFilter ) {
    
    const [ collection , setCollection ] = useState({ loading: true , page : 0 , total : 0, data: []})

    const [ searchInput , setSearchInput ] = useState()

    const [filter, _setFilter] = useState(defaultFilter)

    useEffect(() => {

        if (typeof searchInput === "undefined" ) {
            return;
        }
        const t = setTimeout( () => {
            setFilter({s: searchInput})
        }, 1500 )

        return () => {            
            clearTimeout(t)
        }

    }, [ searchInput ])


    const getSordDirectionByName = n => {
        if ( filter && filter.orderBy && filter.orderBy === n) {
            return filter.order || "none"
        }
    }

    const setFilter = ( addFilter ) => {

        if (typeof addFilter === "function") {
            _setFilter(addFilter)
            return;
        } else if ( typeof addFilter === "boolean" && ! addFilter ) {
            _setFilter( {} ) 
            return
        }

        _setFilter( prevFilter => {

            const nextFilter = Object.assign( {}, prevFilter)

            Object.entries(addFilter).map( ( [ key , value ] ) =>{
                if ( Array.isArray(value) ) { // Bisogna impostare la proprietà
                    nextFilter[key] = value
                } else if ( value ) {
                    nextFilter[key] = [ value ]
                } else {
                    if ( _.has( nextFilter , key ) ) {
                        _.unset( nextFilter , key )
                    }
                }
            } )            

            return nextFilter
        });
    }

    const makeURLQuery = useCallback( tempFilter => {

        let url = baseUrl 

        let query = []

        let f = tempFilter ? Object.assign({}, filter, tempFilter) : filter

        for ( let d in f ) {
            query.push( encodeURIComponent( _.snakeCase(d) ) + "=" + encodeURIComponent( f[d] ) )
        }

        if ( query.length ) {
            url += "?" + query.join("&");
        }

        return url
    }, [ baseUrl , filter ])

    const loadApi = useCallback( ( tempFilter ) => {
    
        const source = axios.CancelToken.source() 

        const url = makeURLQuery(tempFilter)

        axios.get( url , { cancelToken : source.token } )
            .then( response => setCollection( response.data ) )
            .catch( e => {

                if ( axios.isCancel(e) ) return;

                if (e.response) {
                    setCollection( prev => Object.assig({}, prev, { data : [] , error : e.response.data.message }) )
                } else console.log(e);
                
            })

        return source.cancel
    }, [ makeURLQuery ])

    useEffect( () => {
        setCollection( prev => Object.assign({} , prev , { loading : true } ) )
        return loadApi()
    }, [ filter , loadApi ] )    

    const datatableOptions = useCallback( ( columns , customData ) => {

        const cNames =  columns.map( c => c.name )
        
        const cLabels =  columns.map( c => c.label ) 

        let labels = localization.it.MUIDatatableLabels

        if ( collection.loading ) {
            labels.body.noMatch = <div className="py-5"><PreLoaderWidget /></div>
        } else if ( customData && customData.errorMessage ) {
            labels.body.noMatch = customData.errorMessage
        } 
        
        const options = {

            serverSide : true,

            onChangePage : page => _setFilter( filter => Object.assign( {}, filter , { page : page + 1 } ) ),

            onChangeRowsPerPage : per_page => _setFilter( ( p ) => Object.assign( {}, p, { per_page , page : 1 } ) ),

            onSearchChange : setSearchInput,

            onSearchClose : () => searchInput && setSearchInput(),

            searchText : searchInput,

            onFilterChange : ( changedCol , filterList , context ) => {

                if ( context == "reset" ) {

                    setFilter( prev => prev.per_page ? { per_page : prev.per_page } : {} )

                } else if ( context === "chip" ) {

                    setFilter( prev => _.omit(prev, changedCol) )

                } else {

                    const index = cNames.findIndex( ( c ) => c === changedCol )

                    if ( typeof cNames[index] !== "undefined" ) {

                        let nome = cNames[index]

                        setFilter({ [nome] : filterList[index] })

                    }

                }

            }, 

            onRowsDelete : console.log,            

            selectableRowsHeader : false,

            customToolbar : collection.loading ? () => <Tooltip title="Loading..." >
                <IconButton className="fas fa-spinner fa-spin" />
            </Tooltip> : undefined,

            onColumnSortChange : ( changedCol , direction ) => _setFilter( prev => Object.assign( {} , prev, { order : (direction === "descending") ? "desc" : "asc" , orderBy :changedCol } ) ) ,

            serverSideFilterList : cNames ? cNames.map( (name , index ) => {
                let a = _.get(filter, name)
                return a ? [ cLabels[index] + ": " + a ] : []
            } ) : undefined ,

            elevation : 0, // il box-shadow

            print : false,

            download : false,

            page : collection.current_page - 1,

            count : collection.total,

            textLabels : { ... labels },
        }

        return options

    }, [ collection , filter , searchInput ] )

    
    const reload = () => _setFilter( filter )

    return [ collection , datatableOptions , { filter , setFilter , reload , setCollection , getSordDirectionByName } ]

}