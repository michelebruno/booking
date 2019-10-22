import React from "react";
import { Link } from 'react-router-dom';

import ProfileDropdown from './ProfileDropdown';
import logoSm from '../../images/logo-sm.png'; 
import profilePic from '../../images/users/user-1.jpg';


const ProfileMenus = [{
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


const Topbar = props => { 

  	return (
		<React.Fragment>
			<div className="navbar-custom">
				<ul className="list-unstyled topnav-menu float-right mb-0">

					<li className="d-none d-sm-block">
						<form className="app-search">
						<div className="app-search-box">
							<div className="input-group">
							<input type="text" className="form-control" placeholder="Cerca" />
							<div className="input-group-append">
								<button className="btn" >
								<i className="fe-search"></i>
								</button>
							</div>
							</div>
						</div>
						</form>
					</li> 

					<li>
						<ProfileDropdown profilePic={profilePic} menuItems={ProfileMenus} username={'Amministratore'} />
					</li>


					<li className="dropdown notification-list">
						<button className="btn btn-link nav-link right-bar-toggle waves-effect waves-light" onClick={props.rightSidebarToggle}>
						<i className="fe-settings noti-icon"></i>
						</button>
					</li>
				</ul>

				<div className="logo-box">
					<Link to="/" className="logo text-center">
						<span className="logo-lg">
						<img src="https://www.turismo.bologna.it/wp-content/uploads/2019/03/OSCARD-Turismo-Bologna-Logo_Landscape_-Png.png" alt="" height="16" />
						</span>
						<span className="logo-sm">
						<img src={logoSm} alt="" height="24" />
						</span>
					</Link>
				</div>

				<ul className="list-unstyled topnav-menu topnav-menu-left m-0">
					<li>
						<button className="button-menu-mobile disable-btn waves-effect" onClick={props.menuToggle}>
						<i className="fe-menu"></i>
						</button>
					</li>

					<li>
						<h4 className="page-title-main d-inline-block">{props.title}</h4>
						{ typeof props.tastimenu !== 'undefined' && <props.tastimenu {...props} />}

					</li>
				</ul>
				
			</div>
		</React.Fragment >
	);
}


export default Topbar;

