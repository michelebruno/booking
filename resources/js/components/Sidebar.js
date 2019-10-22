import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import { Collapse , Dropdown } from 'react-bootstrap';
import MetisMenu from 'react-metismenu'
import PerfectScrollbar from 'react-perfect-scrollbar';
import profilePic from '../../images/users/user-1.jpg';

const vociMenu = [
    {
        icon: 'mdi mdi-view-dashboard',
        label: 'Dashboard',
        to: '/dashboard'
    },
    {
        label: 'Esercenti',
        content: [
            {
                label: 'Scheda esercente',
                to: '/esercenti/21'
            },
            {
                label: 'Modifica esercente',
                to: '/esercenti/modifica/21'
            }
        ]
    },
    {
        icon: 'fas fa-shopping-cart',
        label: 'Ordini',
        to: '/ordini',
    }
]

let SideNavContent = () => {
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
                            <Link to="/deals/21" className="side-nav-link-ref"> 
                                <span>Scheda deal</span>
                            </Link>
                        </li>
                        <li>
                            <Link to="/servizi/21" className="side-nav-link-ref"> 
                                <span>Scheda servizi</span>
                            </Link>
                        </li>
                        <li>
                            <Link to="/deals/modifica/12" className="side-nav-link-ref">Modifica deal</Link>
                        </li>
                    </ul>
                </li>
                <li>
                    <Link to="/ordini" className="waves-effect side-nav-link-ref" >
                        <i className="dripicons-shopping-bag "></i>
                        <span>Ordini</span>
                    </Link>
                    <ul className="nav-second-level nav" aria-expanded="false">
                        <li>
                            <Link to="/ordini/12" className="side-nav-link-ref">Dettagli ordine</Link>
                        </li> 
                    </ul>
                </li>
                <li>
                    <Link to="/tickets" className="waves-effect side-nav-link-ref" >
                        <i className="fas fa-ticket-alt "></i>
                        <span>Tickets</span>
                    </Link>
                </li>
                <li>
                    <Link to="/esercenti" className="waves-effect side-nav-link-ref has-arrow" aria-expanded="false">
                        <i className="mdi mdi-tooltip-account "></i>
                        <span>Esercenti</span>
                    </Link>
                    <ul className="nav-second-level nav" aria-expanded="false">
                        <li>
                            <Link to="/esercenti/12" className="side-nav-link-ref">Profilo esercente</Link>
                        </li>
                        <li>
                            <Link to="/esercenti/modifica/12" className="side-nav-link-ref">Modifica esercente</Link>
                        </li>
                    </ul>
                </li> 
                <li>
                    <Link to="/" className="waves-effect side-nav-link-ref" >
                        <i className="fe-settings"></i>
                        <span>Impostazioni</span>
                    </Link>
                </li>
            </ul>
        </div>
        <div className="clearfix"></div>
    </React.Fragment>
}


const UserProfile = () => {
    return <React.Fragment>
        <div className="user-box text-center">
            <img src={profilePic} alt="user-img" title="Amministratore" className="rounded-circle img-thumbnail avatar-lg" />
            <Dropdown>
                <Dropdown.Toggle variant="link" className="text-dark dropdown-toggle h5 mt-2 mb-1 d-inline-block">
                    Amministratore
                </Dropdown.Toggle>
                <Dropdown.Menu className="user-pro-dropdown">
                    <Dropdown.Item>
                        <i className="fe-user mr-1"></i>
                        <span>My Account</span>
                    </Dropdown.Item>
                    <Dropdown.Item>
                        <i className="fe-settings mr-1"></i>
                        <span>Settings</span>
                    </Dropdown.Item>
                    <Dropdown.Item>
                        <i className="fe-lock mr-1"></i>
                        <span>Lock Screen</span>
                    </Dropdown.Item>
                    <Dropdown.Item>
                        <i className="fe-log-out mr-1"></i>
                        <span>Logout</span>
                    </Dropdown.Item>
                </Dropdown.Menu>
            </Dropdown> 

            <p className="text-muted">Admin Head</p>
            <ul className="list-inline">
                <li className="list-inline-item">
                    <Link to="/" className="text-muted">
                        <i className="mdi mdi-settings"></i>
                    </Link>
                </li>

                <li className="list-inline-item">
                    <Link to="/" className="text-custom">
                        <i className="mdi mdi-power"></i>
                    </Link>
                </li>
            </ul>
        </div>
    </React.Fragment>
}

const Sidebar = ( props ) => {
    const isCondensed = props.isCondensed || false;

    return(
        <React.Fragment>
            <div className='left-side-menu' >
                {!isCondensed && <PerfectScrollbar><UserProfile /><SideNavContent /></PerfectScrollbar>}
                {isCondensed && <UserProfile /> && <SideNavContent />}
            </div>
        </React.Fragment>
    )
}

export default Sidebar;
