import { AUTHENTICATION_SUCCESSFUL , GET_AUTOLOADED_SETTINGS, SETTING_UPDATED } from "../_constants/action-types"

const initialState = {
    currentUser: false,
    settings: false
}

const rootReducer = ( state = initialState, action ) => {

    if (action.type === AUTHENTICATION_SUCCESSFUL ) {

        window.axios.defaults.headers.common['Authorization'] = "Bearer " + action.payload.api_token

        return Object.assign({}, state, { 
            currentUser : action.payload,
        });
    } 
    

    if (action.type === GET_AUTOLOADED_SETTINGS ) {
 
        return Object.assign({}, state, { 
            settings : action.payload,
        });
    } 
    
    if (action.type === SETTING_UPDATED ) {
        const { payload } = action;

        let obj = {};

        obj[payload.chiave] = payload.valore

        const newSettings = Object.assign({}, state.settings, obj) 
 
        return Object.assign({}, state, { 
            settings : newSettings,
        });
    } 
    
    return state;
}

export default rootReducer;