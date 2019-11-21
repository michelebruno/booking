import React, { Suspense , useState } from "react";
import { Container } from 'react-bootstrap';

import profilePic from '../../images/users/user-1.jpg';

// code splitting and lazy loading
// https://blog.logrocket.com/lazy-loading-components-in-react-16-6-6cea535c0b52
const Topbar = React.lazy(() => import("./Topbar"));
const Sidebar = React.lazy(() => import("./Sidebar"));
const RightSidebar = React.lazy(() => import("./RightSidebar"));
const Footer = React.lazy(() => import("./Footer"));
const loading = () => <div className="text-center"></div>;

const RightSidebarContent = (props) => {
    return <div className="user-box">
        <div className="user-img">
            <img src={profilePic} alt="user-img" title="Amministratore"
                className="rounded-circle img-fluid" />
            <a href="/" className="user-edit"><i className="mdi mdi-pencil"></i></a>
        </div>

        <h5>{props.user && <a href="/">{props.user.username}</a>}</h5>
        <p className="text-muted mb-0"><small>Founder</small></p>
    </div>
}

const AuthLayout = ( props ) => {

    const [isCondensed, setIsCondensed] = useState(false)
    
    const signOut = (e) => {
        e.preventDefault();
        props.history.push("/login");
    }

    /**
     * toggle Menu
     */
    const toggleMenu = (e) => {
        e.preventDefault();
        setIsCondensed( !isCondensed )
    }

    
    /**
     * Toggle right side bar
     */
    const toggleRightSidebar = () => {
        document.body.classList.toggle("right-bar-enabled");
    }

    
    // get the child view which we would like to render
    const children = props.children || null;
    return (
        <div className="app">
            <div id="wrapper">
                <Suspense fallback={loading()}>
                    <Topbar rightSidebarToggle={toggleRightSidebar} menuToggle={toggleMenu} {...props} />
                    <Sidebar isCondensed={isCondensed} { ...props } ></Sidebar>
                </Suspense>
                <div className="content-page">
                    <div className="content">

                        <Container fluid>
                            <Suspense fallback={loading()}>
                                {children}
                            </Suspense>
                        </Container>
                    </div>

                    <Footer />
                </div>
            </div>

            <RightSidebar title={"Settings"}>
                <RightSidebarContent user={props.user} />
            </RightSidebar>
        </div>
    );

}

const mapStateToProps = (state) => {
    return {
        user: state.Auth.user
    }
}
export default AuthLayout;

