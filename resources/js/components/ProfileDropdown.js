import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import { Dropdown } from 'react-bootstrap';


class ProfileDropdown extends Component {
    constructor(props) {
        super(props);

        this.toggleDropdown = this.toggleDropdown.bind(this);
        this.state = {
            dropdownOpen: false
        };
    }

    /*:: toggleDropdown: () => void */
    toggleDropdown() {
        this.setState({
            dropdownOpen: !this.state.dropdownOpen
        });
    }

    render() {
        const profilePic = this.props.profilePic || null;

        return (
            <Dropdown className="notification-list">
                <Dropdown.Toggle className="btn btn-link border-0 nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light">
                    <img src={profilePic} className="rounded-circle" alt="user" />
                    <span className="pro-user-name ml-1">{this.props.username}  <i className="mdi mdi-chevron-down"></i> </span>
                </Dropdown.Toggle>
                <Dropdown.Menu alignRight className="profile-dropdown">
                    <div >
                        <div className="dropdown-header noti-title">
                            <h6 className="text-overflow m-0">Ciao!</h6>
                        </div>
                        {this.props.menuItems.map((item, i) => {
                            return <Dropdown.Item as="span" key={i + "-profile-menu"}>
                                {item.hasDivider ? <Dropdown.Divider /> : null}
                                <Link to={item.redirectTo} className="dropdown-item notify-item">
                                    <i className={`${item.icon} mr-1`}></i>
                                    <span>{item.label}</span>
                                </Link>
                            </Dropdown.Item>
                        })}
                    </div>
                </Dropdown.Menu>
            </Dropdown>
        );
    }
}

export default ProfileDropdown;