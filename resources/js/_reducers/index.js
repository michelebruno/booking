import { AUTHENTICATION_SUCCESSFUL } from "../_constants/action-types"

const initialState = {
    currentUser: false
}

const rootReducer = ( state = initialState, action ) => {

    if (action.type === AUTHENTICATION_SUCCESSFUL) {
        return Object.assign({}, state, { 
            currentUser : action.payload
        });
    } 
    
    return state;
}

export default rootReducer;