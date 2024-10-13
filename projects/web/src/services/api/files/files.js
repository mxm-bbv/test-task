import { check } from "./methods/check.js";
import {store} from "@/services/api/files/methods/store.js";
import {destroy} from "@/services/api/files/methods/destroy.js";

class FilesApi {
    async check(file = null) {
        return check(file);
    }

    async store(file, name) {
        return store(file, name)
    }

    async destroy(file) {
        return destroy(file);
    }
}

export default new FilesApi();