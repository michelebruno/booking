/* eslint-disable react/prop-types */
import React from "react"
import { connect } from "react-redux"

const UtentiProfilo = React.lazy( () => import('./UtentiProfilo'));
const EsercentiCrea = React.lazy( () => import('./EsercentiCrea'));


const Account = ( { currentUser , ...props }) => {
    /* if (currentUser.ruolo == 'admin' || currentUser.ruolo == 'account_manager' ) {
        return <React.Suspense>
            <UtentiProfilo { ...props } utente={currentUser} isCurrentUser />
        </React.Suspense>
    } else  */if ( currentUser.ruolo == 'esercente') {
        return <React.Suspense>
            <EsercentiCrea { ...props } esercente={currentUser} isCurrentUser shouldBeReloaded />
        </React.Suspense>
    }
}


export default connect( state => {
    return {
        currentUser : state.currentUser
    }
})(Account)