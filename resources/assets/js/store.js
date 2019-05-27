import { getLocalUser } from "./helpers/auth";

const user = getLocalUser();

export default {
    state:{
        currentUser: user,
        isLoggedIn: !!user,
        loading: false,
        auth_error: null
    }
    , mutations:{
        login(state) {
            state.loading = true;
            state.auth_error = null;
        },
        loginSuccess(state, payload) {
            state.auth_error = null;
            state.isLoggedIn = true;
            state.loading = false;

            // Create new object currentUser and put the access_token user object inside the currentUser obj.
            // So the currentUser will be:
            /*
                {
                    "user_id": number,
                    "name": string,
                    "email": string,
                    "created_at": datetime,
                    "updated_at": datetime,
                    "deleted_at": datetime
                    "token": string
                }
             */
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});

            // Save the currentUser in localStorage.
            localStorage.setItem("user", JSON.stringify(state.currentUser));
        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");
            state.isLoggedIn = false;
            state.currentUser = null;
        }
    }
    , getters:{
        isLoading(state) {
            return state.loading;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        currentUser(state) {
            return state.currentUser;
        },
        authError(state) {
            return state.auth_error;
        }

    }
    , actions:{
        login(context) {
            context.commit("login");
        }
    }
};