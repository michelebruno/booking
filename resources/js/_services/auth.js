import { authenticate , getAutoloadedSettings } from "../_actions"

const login = () => {
    return dispatch => {
        return axios.get( "/account", {withCredentials: true} )
            .then( res => {
                return dispatch( authenticate(res.data) )
            } )
    }
}

const settings = () => {
    return dispatch => {
        return axios.get( "/settings" )
            .then(
                res => dispatch( getAutoloadedSettings( res.data ) )
            )
    }
}

export default { login , settings };