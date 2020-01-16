/* eslint-disable react/prop-types */
import React from 'react';
import { Link } from 'react-router-dom';
import { Dropdown } from 'react-bootstrap'; 
import PerfectScrollbar from 'react-perfect-scrollbar';
import { connect } from "react-redux"

const SideNavContent = ( { user } ) => {
    return <React.Fragment>

        <div id="sidebar-menu">
            <ul className="metismenu" id="side-menu">
                <li className="menu-title">Menu</li>

                <li>
                    <Link to="/dashboard" className="waves-effect side-nav-link-ref">
                        <i className="mdi mdi-view-dashboard"></i>
                        <span>Dashboard </span>
                    </Link>
                 </li>
                <li>
                    <Link to="/deals" className="waves-effect side-nav-link-ref" >
                        <i className="fas fa-shopping-cart "></i>
                        <span>Prodotti</span>
                    </Link>
                    <ul className="nav-second-level nav" >
                        <li>
                            <Link to="/deals/d-2" className="side-nav-link-ref"> 
                                <span>Scheda deal</span>
                            </Link>
                        </li>
                        <li>
                            <Link to="/deals/crea" className="side-nav-link-ref">Crea deal</Link>
                        </li>
                    </ul>
                </li>
                <li>
                    <Link to="/ordini" className="waves-effect side-nav-link-ref" >
                        <i className="fas fa-shopping "></i>
                        <span>Ordini</span>
                    </Link>
                </li>{/*
                <li>
                    <Link to="/tickets" className="waves-effect side-nav-link-ref" >
                        <i className="fas fa-ticket-alt "></i>
                        <span>Tickets</span>
                    </Link>
                </li> */}
                { [ 'esercente' ].indexOf(user.ruolo) !== -1 && <li>
                    <Link to="/account" >
                        <i className="fas fa-user" />
                        <span>Il mio account</span>
                    </Link>
                </li>}
                { [ 'admin' , 'account_manager' ].indexOf(user.ruolo) !== -1 && <li>
                    <Link to="/clienti" >
                        <i className="fas fa-user-friends" />
                        <span>Clienti</span>
                    </Link>
                </li>} 
                { [ 'admin' , 'account_manager' ].indexOf(user.ruolo) !== -1 && <li>
                    <Link to="/utenti" >
                        <i className="fas fa-user" />
                        <span>Utenti</span>
                    </Link>
                </li>} 
                { [ 'admin' , 'account_manager' ].indexOf(user.ruolo) !== -1 &&<li>
                    <Link to="/esercenti" className="waves-effect side-nav-link-ref" aria-expanded="false">
                        <i className="mdi mdi-tooltip-account "></i>
                        <span>Esercenti</span>
                    </Link>
                </li> } 
                { [ 'admin' ].indexOf(user.ruolo) !== -1 && <li>
                    <Link to="/settings" className="waves-effect side-nav-link-ref" >
                        <i className="mdi mdi-settings"></i>
                        <span>Impostazioni</span>
                    </Link>
                </li> }
            </ul>
        </div>
        <div className="clearfix"></div>
    </React.Fragment>
}


const UserProfile = ( { user } ) => {
    const profilePic = ( typeof user.links !== 'undefined' && typeof user.links.avatar !== 'undefined' ) ? user.links.avatar : false;
    return <React.Fragment>
        <div className="user-box text-center">
            { profilePic && <img src={profilePic} alt="user-img" title="Amministratore" className="rounded-circle img-thumbnail avatar-lg" /> }
            { !profilePic && <div className="d-inline-block rounded-circle" ><i className="fas fa-user h-100 w-100 d-block" /> </div> }
            <Dropdown>
                <Dropdown.Toggle variant="link" className="text-dark dropdown-toggle h5 mt-2 mb-1 d-inline-block">
                    { user.username }
                </Dropdown.Toggle>
                <Dropdown.Menu className="user-pro-dropdown">
                    <Dropdown.Item as={Link} to="/account" >
                        <i className="fe-user mr-1"></i>
                        <span>Il mio account</span>
                    </Dropdown.Item>
                    <Dropdown.Item as={Link} to="/account" >
                        <i className="fe-settings mr-1"></i>
                        <span>Impostazioni</span>
                    </Dropdown.Item>
                    <Dropdown.Item href="/logout">
                        <i className="fe-log-out mr-1"></i>
                        <span>Logout</span>
                    </Dropdown.Item>
                </Dropdown.Menu>
            </Dropdown> 

            <ul className="list-inline">
                <li className="list-inline-item">
                    <Link to="/" className="text-muted">
                        <i className="mdi mdi-settings"></i>
                    </Link>
                </li>

                <li className="list-inline-item">
                    <a href="/logout" className="text-custom">
                        <i className="mdi mdi-power"></i>
                    </a>
                </li>
            </ul>
        </div>
    </React.Fragment>
}

const Sidebar = ( { currentUser , ...props } ) => {
    
    if ( ! props.isCondensed) {
        document.body.classList.remove("sidebar-enable");
        document.body.classList.remove("enlarged");
    } else {
        document.body.classList.add("sidebar-enable");
        const isSmallScreen = window.innerWidth < 768;
        if (!isSmallScreen) {
            document.body.classList.add("enlarged");
        }
    }

    
    
    return(
        <React.Fragment>
            <div className='left-side-menu' >
                { ! props.isCondensed && <PerfectScrollbar>
                    <SideNavContent user={ currentUser } />
                </PerfectScrollbar> }
                { props.isCondensed && <SideNavContent user={ currentUser }/> }
            </div>
        </React.Fragment>
    )
}

const mapStateToProps = state => {
    return {
        currentUser : state.currentUser
    }
}

export default connect( mapStateToProps )(Sidebar);
