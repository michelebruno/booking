import { authenticate } from "../_actions"

const login = () => {
    return dispatch => {
        return axios.get( "/auth", {withCredentials: true} )
            .then( res => {
                return dispatch( authenticate(res.data) )
            } )
    }
}

export default { login };