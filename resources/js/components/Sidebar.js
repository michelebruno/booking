/* eslint-disable react/prop-types */
import React from 'react';
import { Link, useRouteMatch } from 'react-router-dom';
import PerfectScrollbar from 'react-perfect-scrollbar';
import { connect } from "react-redux"

import ListSubheader from '@material-ui/core/ListSubheader';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import Collapse from '@material-ui/core/Collapse';
import { makeStyles } from '@material-ui/core/styles';

import ShoppingCartIcon from '@material-ui/icons/ShoppingCart';
import ExpandLess from '@material-ui/icons/ExpandLess';
import ExpandMore from '@material-ui/icons/ExpandMore';
import Person from '@material-ui/icons/Person';
import SupervisorAccountIcon from '@material-ui/icons/SupervisorAccount';
import StorefrontIcon from '@material-ui/icons/Storefront';
import StoreIcon from '@material-ui/icons/Store';
import SettingsIcon from '@material-ui/icons/Settings';
import PeopleIcon from '@material-ui/icons/People';



const SideNavContent = ({ user }) => {

    const classes = makeStyles(theme => ({
        root: {
            width: '100%',
            maxWidth: 360,
        },
        nested: {
            paddingLeft: theme.spacing(4),
        },
    }))()

    const items = [
        {
            label: "Dashboard",
            icon: <i className="mdi mdi-view-dashboard"></i>,
            pathname: "/dashboard",
        },
        {
            label: "Prodotti",
            icon: <StoreIcon />,
            roles: ['admin', 'account_manager'],
            subItems: [
                {
                    pathname: "/deals",
                    label: "Deals",
                },
                {
                    pathname: "/tariffe/",
                    label: "Tariffe",
                },
            ],
        },
        {
            label: "Ordini",
            icon: <ShoppingCartIcon />,
            pathname: "/ordini",
            roles: ['admin', 'account_manager'],
        },
        {
            label: "Utenti",
            pathname: "/utenti",
            icon: <SupervisorAccountIcon />,
            roles: ["admin", "account_manager"],
        },
        {
            label: "Clienti",
            pathname: "/clienti",
            icon: <PeopleIcon />,
            roles: ["admin", "account_manager"],
        },
        {
            label: "Fornitori",
            pathname: "/fornitori",
            icon: <StorefrontIcon />,
            roles: ["admin", "account_manager"],
        },
        {
            label: "Impostazioni",
            pathname: "/settings",
            icon: <SettingsIcon />,
            roles: ["admin"],
        },
        {
            label: "Il mio profilo",
            icon: <Person />,
            roles: ["fornitore"],
        },

    ]


    const SidebarItem = ({ roles, label, icon, pathname, subItems }) => {

        const match = useRouteMatch(pathname)
        const [open, setOpen] = React.useState(Boolean(match))

        const renderLink = React.useMemo(
            () =>
                React.forwardRef((linkProps, ref) => (
                    <Link ref={ref} to={pathname} {...linkProps} />
                )),
            [pathname],
        );


        React.useEffect(() => {
            if (match) {
                setOpen(true)
            }
        }, [match])

        /**
         * TODO Se la tendina è aperta perché siamo nella pagina corrispondente, non deve essere possibile chiuderla.
         */
        const SidebarSubItem = ({ label, pathname, icon, roles }) => {

            const renderLink = React.useMemo(
                () =>
                    React.forwardRef((linkProps, ref) => (
                        <Link ref={ref} to={pathname} {...linkProps} />
                    )),
                [pathname],
            );

            const match = useRouteMatch(pathname)

            React.useEffect(() => {
                if (match) {
                    setOpen(true)
                }
            }, [match])

            return (!Array.isArray(roles) || roles.indexOf(user.ruolo) !== -1)
                && <ListItem button className={classes.nested} component={renderLink} dense={true} selected={Boolean(match)} >
                    {icon && <ListItemIcon>
                        {icon}
                    </ListItemIcon>}
                    <ListItemText primary={label} inset={!icon} />
                </ListItem>
        }


        return (!Array.isArray(roles) || roles.indexOf(user.ruolo) !== -1) && <>
            <ListItem button component={renderLink} selected={match ? match.isExact : false}>
                {icon && <ListItemIcon>
                    {icon}
                </ListItemIcon>}
                <ListItemText primary={label} inset={!icon} />

                {subItems && (open ? <ExpandLess onClick={(e) => {
                    e.preventDefault();
                    e.stopPropagation()
                    setOpen(!open)
                }} /> : <ExpandMore onClick={(e) => {
                    e.preventDefault();
                    e.stopPropagation()
                    setOpen(!open)
                }} />)}

            </ListItem>
            {subItems &&
                <Collapse in={open} timeout={1000} >
                    <List component="div" disablePadding >
                        {subItems.map((props, index) => <SidebarSubItem key={index} {...props} />)}
                    </List>
                </Collapse>
            }
        </>
    }

    return <>

        <div id="sidebar-menu">
            <List className={classes.root} component="nav" subheader={<ListSubheader component="div" id="nested-list-subheader">Menu</ListSubheader>}>

                {items.map((item, index) => <SidebarItem {...item} key={index} />)}

            </List>
        </div>
        <div className="clearfix"></div>
    </>
}

const Sidebar = ({ currentUser, ...props }) => {

    if (!props.isCondensed) {
        document.body.classList.remove("sidebar-enable");
        document.body.classList.remove("enlarged");
    } else {
        document.body.classList.add("sidebar-enable");
        const isSmallScreen = window.innerWidth < 768;
        if (!isSmallScreen) {
            document.body.classList.add("enlarged");
        }
    }

    return (
        <React.Fragment>
            <div className='left-side-menu' >
                {!props.isCondensed && <PerfectScrollbar>
                    <SideNavContent user={currentUser} />
                </PerfectScrollbar>}
                {props.isCondensed && <SideNavContent user={currentUser} />}
            </div>
        </React.Fragment>
    )
}

const mapStateToProps = state => {
    return {
        currentUser: state.currentUser,
    }
}

export default connect(mapStateToProps)(Sidebar);
