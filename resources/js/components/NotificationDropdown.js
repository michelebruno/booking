import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import { Dropdown } from 'react-bootstrap';

import PerfectScrollbar from 'react-perfect-scrollbar';

const notificationContainerStyle = {
    'maxHeight': '230px'
};

const NotificationDropdown = ( props ) => {

    const [ dropdownOpen, setDropdownOpen ] = React.useState(false);

    const getRedirectUrl = (item) => {
        return `/notification/${item.id}`;
    }
    
    return (
        <Dropdown className="notification-list">
            <Dropdown.Toggle variant="link">
                <i className="fe-bell noti-icon"></i>
                <span className="badge badge-danger rounded-circle noti-icon-badge">9</span>
            </Dropdown.Toggle>
            <Dropdown.Menu alignRight className="dropdown-lg">
                <div >
                    <div className="dropdown-item noti-title">
                        <h5 className="m-0">
                            <span className="float-right">
                                <Link to="/notifications" className="text-dark">
                                    <small>Clear All</small>
                                </Link>
                            </span>Notification
                            </h5>
                    </div>
                    <PerfectScrollbar style={notificationContainerStyle}>
                        {props.notifications.map((item, i) => {
                            return <Link to={getRedirectUrl(item)} className="dropdown-item notify-item" key={i + "-noti"}>
                                <div className={`notify-icon bg-${item.bgColor}`}>
                                    <i className={item.icon}></i>
                                </div>
                                <p className="notify-details">{item.text}
                                    <small className="text-muted">{item.subText}</small>
                                </p>
                            </Link>
                        })}
                    </PerfectScrollbar>

                    <Link to="/" className="dropdown-item text-center text-primary notify-item notify-all">View All</Link>
                </div>
            </Dropdown.Menu>
        </Dropdown>
    );
}

export default NotificationDropdown;