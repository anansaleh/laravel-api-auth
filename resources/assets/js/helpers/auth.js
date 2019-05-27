import { setAuthorization } from "./general";
export function login(credentials) {
    return new Promise((res, rej) => {
        axios.post('/api/login', credentials)
            .then((response) => {
                setAuthorization(response.data.access_token);
                res(response.data);
            })
            .catch((err) =>{
                rej("Wrong email or password");
            })
    })
}

/**
 * Check if user is exit in localStorage and then get it
 * @returns {null|any}
 */
export function getLocalUser() {
    const userStr = localStorage.getItem("user");

    if(!userStr) {
        return null;
    }

    return JSON.parse(userStr);
}