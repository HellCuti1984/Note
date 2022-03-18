import { createStore } from "vuex"
import { Notes } from "./modules/notes"

export const store = createStore({
    modules: {
        Notes,
    }
})