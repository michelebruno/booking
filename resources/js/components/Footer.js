import React, { Component } from 'react';

/**
 * Renders the Footer
 */
class Footer extends Component {

    render() {
        return (
            <footer className="footer">
                <div className="container-fluid">
                    <div className="row">
                        <div className="col-md-6">
                            Back end per <a href="https://www.turismo.bologna.it">turismo.bologna.it</a>
                        </div>
                    </div>
                </div>
            </footer>
        )
    }
}

export default Footer;