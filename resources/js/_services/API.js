import axios from "axios";
import store from "../_store"

let headers = {
    'X-Requested-With' : 'XMLHttpRequest',
}
const currentUser = store.currentUser

if (currentUser && currentUser.token) {
    headers['Authorization'] = 'Bearer ' + currentUser.token
}

const API = axios.create({
    baseURL: "http://localhost:3000/api/v1",
    responseType: "json"
});

export default API;

