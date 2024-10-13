import {register} from "./methods/register.js";

class UserApi {
    static async register() {
        return register();
    }
}

export default UserApi;