import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import { Dropdown } from 'react-bootstrap';
import { connect } from 'react-redux';


const menuItems = [{
    label: 'Il mio account',
    icon: 'fe-user',
    redirectTo: "/",
  },
  {
    label: 'Impostazione',
    icon: 'fe-settings',
    redirectTo: "/"
  },
  {
    label: 'Blocca',
    icon: 'fe-lock',
    redirectTo: "/"
  },
  {
    label: 'Logout',
    icon: 'fe-log-out',
    redirectTo: "/logout",
    hasDivider: true
  }]

const ProfileDropdown = props => {
    const profilePic = props.profilePic || null;

    return (
        <Dropdown className="notification-list">
            <Dropdown.Toggle className="btn btn-link border-0 nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light">
                <img src={profilePic} className="rounded-circle" alt="user" />
                <span className="pro-user-name ml-1">{props.currentUser.username}  <i className="mdi mdi-chevron-down"></i> </span>
            </Dropdown.Toggle>
            <Dropdown.Menu alignRight className="profile-dropdown">
                <div >
                    <div className="dropdown-header noti-title">
                        <h6 className="text-overflow m-0">Ciao!</h6>
                    </div>
                    {menuItems.map((item, i) => {
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

// class ProfileDropdown extends Component {
//     constructor(props) {
//         super(props);

//         this.toggleDropdown = this.toggleDropdown.bind(this);
//         this.state = {
//             dropdownOpen: false
//         };
//     }

//     render() {

//     }
// }
const mapStateToProps = state => {
    return {currentUser : state.currentUser}
}
export default connect( mapStateToProps )(ProfileDropdown);