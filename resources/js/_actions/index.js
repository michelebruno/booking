import { AUTHENTICATION_SUCCESSFUL , GET_AUTOLOADED_SETTINGS , SETTING_UPDATED, SET_TOPBAR_BUTTONS } from "../_constants/action-types"

export function authenticate(payload) {
    return { type: AUTHENTICATION_SUCCESSFUL, payload }
};

export function getAutoloadedSettings( payload ) {
    return { type : GET_AUTOLOADED_SETTINGS , payload }
}

export function settingUpdated( payload ) { 
    return { type: SETTING_UPDATED , payload }
 }

 export function setTopbarButtons( payload ) {
    return { type : SET_TOPBAR_BUTTONS , payload  }
}

export function unsetTopbarButtons( payload ) {
    return { type : SET_TOPBAR_BUTTONS , payload : () => null }
}