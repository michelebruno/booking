import React, { useState, useEffect, useCallback, useImperativeHandle, forwardRef } from 'react'
import PropTypes from 'prop-types'
import localization from '../_services/localization';

import MUIDataTable from "mui-datatables"
import { Tooltip, IconButton } from '@material-ui/core';
import PreLoaderWidget from './Loader';


const ServerDataTable = forwardRef(function ServerDataTable({ defaultFilter, url, columns, options, editable, title, onCollectionChange }, ref) {

    /**
     * * React Hooks
     */
    const [collection, setCollection] = useState({ init: true, page: 0, total: 0, data: [] })

    const [_columns, setColumns] = useState(columns)

    /**
     * 
     * * Filtro Server-side
     * 
     */
    const [searchInput, setSearchInput] = useState()

    const [filter, setFilter] = useState(defaultFilter)

    const [timeoutFilter, setTimeoutFilter] = useState()

    useEffect(() => {

        if (typeof timeoutFilter === "undefined") {
            return;
        }
        const t = setTimeout(() => {
            setFilter(f => ({ ...f, ...timeoutFilter }))
            setTimeoutFilter()
        }, 1000)

        return () => {
            clearTimeout(t)
        }

    }, [timeoutFilter])

    useEffect(() => {

        if (typeof searchInput === "undefined") {
            return;
        }
        const t = setTimeout(() => {
            setFilter(f => ({ ...f, s: searchInput }))
        }, 1500)

        return () => {
            clearTimeout(t)
        }

    }, [searchInput])

    const makeURLQuery = useCallback(tempFilter => {

        let _url = url

        let query = []

        let f = tempFilter ? Object.assign({}, filter, tempFilter) : filter

        for (let d in f) {
            query.push(encodeURIComponent(_.snakeCase(d)) + "=" + encodeURIComponent(f[d]))
        }

        if (query.length) {
            _url += "?" + query.join("&");
        }

        return _url;

    }, [url, filter])

    const loadApi = useCallback((tempFilter) => {

        const source = axios.CancelToken.source()

        const url = makeURLQuery(tempFilter)

        axios.get(url, { cancelToken: source.token })
            .then(response => setCollection(response.data))
            .catch(e => {

                if (axios.isCancel(e)) return;

                if (e.response) {
                    setCollection(prev => Object.assign({}, prev, { data: [], error: e.response.data.message }))
                    onCollectionChange(e.response)
                } else console.warn("Errore axios inaspettato: ", e);

            })

        return source.cancel
    }, [makeURLQuery, onCollectionChange])

    useEffect(() => {
        setCollection(collection => ({ ...collection, loading: true }))
        return loadApi()
    }, [filter])


    const getRow = useCallback((index) => collection.data[index], [collection])
    // const getCollection = useCallback(() => collection, [collection])

    useImperativeHandle(
        ref,
        () => ({
            reload: () => loadApi(),
            getRow,
        }),
        [loadApi],
    )

    /**
     * 
     * * COLONNE
     * 
     */

    const customBodyRender = useCallback(
        (a, b, customBodyRender) => {
            customBodyRender(a, { ...b, row: collection.data[b.rowIndex] })
        },
        [collection],
    )

    useEffect(() => {
        let colonne = columns.map(colonna => {

            if (typeof colonna.options == "undefined") {
                colonna.options = {}
            }

            if (filter && filter.orderBy && filter.orderBy === colonna.name) {
                colonna.options.sortDirection = filter.order || "none"
            }

            if (filter && filter[colonna.name]) {
                colonna.options.filterList = (timeoutFilter && timeoutFilter[colonna.name]) || filter[colonna.name]
            }

            if (colonna.customBodyRender) {
                colonna.customBodyRender = (a, b) => customBodyRender(a, b, colonna.customBodyRender)
            }

            return colonna
        })

        setColumns(colonne)

    }, [filter, collection, collection.data])

    /**
     * 
     * * OPTIONS
     * 
     */

    const cNames = columns.map(c => c.name)

    const cLabels = columns.map(c => c.label)

    let labels = localization.it.MUIDatatableLabels

    let _options = {

        serverSide: true,

        onChangePage: page => setFilter(filter => Object.assign({}, filter, { page: page + 1 })),

        onChangeRowsPerPage: per_page => setFilter((p) => Object.assign({}, p, { per_page, page: 1 })),

        onSearchChange: setSearchInput,

        onSearchClose: () => searchInput && setSearchInput(),

        searchText: searchInput,

        onFilterChange: (changedCol, filterList, context) => {

            if (context == "reset") {

                setFilter(prev => prev.per_page ? { per_page: prev.per_page } : {})

            } else if (context === "chip") {

                setFilter(prev => _.omit(prev, changedCol))

            } else {

                const index = cNames.findIndex((c) => c === changedCol)

                if (typeof _columns[index] !== "undefined") {
                    const colonna = _columns[index]
                    let nome = colonna.name

                    let value = filterList[index]

                    if (!value || !value.length || value == "") { // Se il valore è nullo, elimina la proprietà dell'oggetto.
                        setFilter(prev => _.omit(prev, changedCol))
                    } else if (colonna.options && colonna) {
                        setTimeoutFilter({ [nome]: value })
                    } else {
                        setFilter(f => ({ ...f, [nome]: value }))
                    }


                }

            }

        },

        onRowsDelete: console.log,

        selectableRowsHeader: false,

        customToolbar: collection.loading ? () => <Tooltip title="Loading..." >
            <IconButton className="fas fa-spinner fa-spin" />
        </Tooltip> : undefined,

        onColumnSortChange: (changedCol, direction) => setFilter(prev => Object.assign({}, prev, { order: (direction === "descending") ? "desc" : "asc", orderBy: changedCol })),

        serverSideFilterList: cNames ? cNames.map((name, index) => {
            let a = _.get(filter, name)
            return a ? [cLabels[index] + ": " + a] : []
        }) : undefined,

        elevation: 0, // il box-shadow

        print: false,

        download: false,

        page: collection && collection.current_page ? collection.current_page - 1 : undefined,

        count: collection && collection.total ? collection.total : undefined,

        textLabels: { ...labels },
    }

    if (!collection || collection.init || !_columns) {
        return <div style={{ padding: "auto 15px" }}>
            <PreLoaderWidget />
        </div>
    }

    return <MUIDataTable
        title={title}
        data={collection.data}
        columns={_columns}
        options={{ ..._options, options }}
    />
})

ServerDataTable.defaultProps = {
    title: "Tabella",
    onCollectionChange: () => null,
}
ServerDataTable.propTypes = {

    url: PropTypes.string.isRequired,
    onCollectionChange: PropTypes.func,
    defaultFilter: PropTypes.object,
    editable: PropTypes.bool,
    /** 
     * 
     * MUIDataTable.propTypes from https://github.com/gregnb/mui-datatables/blob/master/src/MUIDataTable.js 
     * -- data
     */
    title: PropTypes.oneOfType([PropTypes.string, PropTypes.element]).isRequired,
    columns: PropTypes.PropTypes.arrayOf(
        PropTypes.oneOfType([
            PropTypes.string,
            PropTypes.shape({
                label: PropTypes.string,
                name: PropTypes.string.isRequired,
                options: PropTypes.shape({
                    display: PropTypes.oneOf(['true', 'false', 'excluded']),
                    empty: PropTypes.bool,
                    filter: PropTypes.bool,
                    sort: PropTypes.bool,
                    print: PropTypes.bool,
                    searchable: PropTypes.bool,
                    download: PropTypes.bool,
                    viewColumns: PropTypes.bool,
                    filterList: PropTypes.array,
                    sortDirection: PropTypes.oneOf(['asc', 'desc', 'none']),
                    filterOptions: PropTypes.oneOfType([
                        PropTypes.array,
                        PropTypes.shape({
                            names: PropTypes.array,
                            logic: PropTypes.func,
                            display: PropTypes.func,
                        }),
                    ]),
                    filterType: PropTypes.oneOf(['dropdown', 'checkbox', 'multiselect', 'textField', 'custom']),
                    customHeadRender: PropTypes.func,
                    customBodyRender: PropTypes.func,
                    customFilterListOptions: PropTypes.oneOfType([
                        PropTypes.shape({
                            render: PropTypes.func,
                            update: PropTypes.func,
                        }),
                    ]),
                    customFilterListRender: PropTypes.func,
                    setCellProps: PropTypes.func,
                    setCellHeaderProps: PropTypes.func,
                }),
            }),
        ]),
    ).isRequired,
    options: PropTypes.shape({
        responsive: PropTypes.oneOf([
            'stacked',
            'stackedFullWidth',
            'scrollMaxHeight',
            'scrollFullHeight',
            'scrollFullHeightFullWidth',
        ]),
        filterType: PropTypes.oneOf(['dropdown', 'checkbox', 'multiselect', 'textField', 'custom']),
        getTextLabels: PropTypes.func,
        pagination: PropTypes.bool,
        expandableRows: PropTypes.bool,
        expandableRowsOnClick: PropTypes.bool,
        renderExpandableRow: PropTypes.func,
        customToolbar: PropTypes.oneOfType([PropTypes.func, PropTypes.element]),
        customToolbarSelect: PropTypes.oneOfType([PropTypes.func, PropTypes.element]),
        customFooter: PropTypes.oneOfType([PropTypes.func, PropTypes.element]),
        customSearchRender: PropTypes.oneOfType([PropTypes.func, PropTypes.element]),
        customRowRender: PropTypes.func,
        customFilterDialogFooter: PropTypes.func,
        onRowClick: PropTypes.func,
        onRowsExpand: PropTypes.func,
        onRowsSelect: PropTypes.func,
        resizableColumns: PropTypes.bool,
        selectableRows: PropTypes.oneOfType([PropTypes.bool, PropTypes.oneOf(['none', 'single', 'multiple'])]),
        selectableRowsOnClick: PropTypes.bool,
        isRowSelectable: PropTypes.func,
        disableToolbarSelect: PropTypes.bool,
        isRowExpandable: PropTypes.func,
        selectableRowsHeader: PropTypes.bool,
        serverSide: PropTypes.bool,
        onFilterChange: PropTypes.func,
        onFilterDialogOpen: PropTypes.func,
        onFilterDialogClose: PropTypes.func,
        onTableChange: PropTypes.func,
        onTableInit: PropTypes.func,
        caseSensitive: PropTypes.bool,
        rowHover: PropTypes.bool,
        fixedHeader: PropTypes.bool,
        fixedHeaderOptions: PropTypes.shape({
            xAxis: PropTypes.bool,
            yAxis: PropTypes.bool,
        }),
        page: PropTypes.number,
        count: PropTypes.number,
        rowsSelected: PropTypes.array,
        rowsExpanded: PropTypes.array,
        rowsPerPage: PropTypes.number,
        rowsPerPageOptions: PropTypes.array,
        filter: PropTypes.bool,
        sort: PropTypes.bool,
        customSort: PropTypes.func,
        customSearch: PropTypes.func,
        search: PropTypes.bool,
        searchOpen: PropTypes.bool,
        searchText: PropTypes.string,
        searchPlaceholder: PropTypes.string,
        print: PropTypes.bool,
        viewColumns: PropTypes.bool,
        download: PropTypes.bool,
        downloadOptions: PropTypes.shape({
            filename: PropTypes.string,
            separator: PropTypes.string,
            filterOptions: PropTypes.shape({
                useDisplayedColumnsOnly: PropTypes.bool,
                useDisplayedRowsOnly: PropTypes.bool,
            }),
        }),
        onDownload: PropTypes.func,
        setTableProps: PropTypes.func,
        setRowProps: PropTypes.func,
    }),
    className: PropTypes.string,
}

export default ServerDataTable