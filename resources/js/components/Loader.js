import React from 'react';

/**
 * Renders the preloader
 */
const PreLoaderWidget = ( ) => {

    return (
        <div className="preloader" style={ { zIndex : 25 }}>
            <div className="status">
                <div className="spinner-border avatar-sm text-primary m-2" role="status"></div>
            </div>
        </div>
    )
}

export default PreLoaderWidget;