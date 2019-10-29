import { authenticate } from "../_actions"
import { AUTHENTICATION_SUCCESSFUL } from "../_constants/action-types";


const login = () => {
    return dispatch => {
        return axios.get( "/auth", {withCredentials: true} )
            .then( res => {
                return dispatch( authenticate(res.data) )
            } ) 
    }
}

export default { login };