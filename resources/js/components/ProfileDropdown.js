import React from 'react';
import { Link } from 'react-router-dom';
import { Dropdown } from 'react-bootstrap';
import { connect } from 'react-redux';


const menuItems = [{
    label: 'Il mio account',
    icon: 'fe-user',
    redirectTo: "/account",
  },
  {
    label: 'Impostazione',
    icon: 'fe-settings',
    redirectTo: "/account/modifica"
  },]

const ProfileDropdown = props => {
    const profilePic = props.profilePic || null;

    return (
        <Dropdown className="notification-list">
            <Dropdown.Toggle className="btn btn-link border-0 nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light">
                { profilePic && <img src={profilePic} className="rounded-circle" alt="user" /> }
                { ! profilePic && <div className="rounded-circle d-inline-block" ><i className="fas fa-user" /></div> }
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
                    <Dropdown.Item as="span" >
                             <Dropdown.Divider /> 
                            <form action={ props.settings.app_url + "/logout"} >

                            <button type="submit" className="dropdown-item notify-item">
                                <i className={` mr-1`}></i>
                                <span>Logout</span>
                            </button>
                            </form>
                        </Dropdown.Item>
                </div>
            </Dropdown.Menu>
        </Dropdown>
    );
}

const mapStateToProps = state => {
    return {currentUser : state.currentUser, settings : state.settings }
}
export default connect( mapStateToProps )(ProfileDropdown);