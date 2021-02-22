/* eslint-disable react/prop-types */
import React from "react";
import {Link} from 'react-router-dom';
import {connect} from "react-redux"
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import { makeStyles } from '@material-ui/core/styles';

import Button from '@material-ui/core/Button';
import IconButton from '@material-ui/core/IconButton';
import MenuIcon from '@material-ui/icons/Menu';
import Typography from '@material-ui/core/Typography';

import ProfileDropdown from './ProfileDropdown';
// import logoSm from '../../images/logo-sm.png';

const useStyles = makeStyles((theme) => ({
    root: {
        flexGrow: 1,
    },
    menuButton: {
        marginRight: theme.spacing(2),
    },
    title: {
        flexGrow: 1,
    },
}));
const Topbar = ({TopbarButtons, ...props}) => {

    const classes = useStyles()
    return (
        <>
            <AppBar position={"fixed"}>
                <Toolbar>
                    <IconButton edge="start" className={classes.menuButton} color="inherit" aria-label="menu">
                        <MenuIcon />
                    </IconButton>
                    <Typography variant="h6" className={classes.title}>
                        News
                    </Typography>
                    <TopbarButtons />
                    <Button color="inherit">Login</Button>

                </Toolbar>
            </AppBar>
        </ >
    );
}


export default connect(state => {
    return {TopbarButtons: state.TopbarButtons}
})(Topbar);

