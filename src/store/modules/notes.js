import axios from "axios"
const notes_api = 'http://localhost:8000/api'
export const Notes = {
    state: {
        notes: []
    },
    getters: {
        NOTES: state => state.notes
    },
    actions: {
        async GET_NOTES({ commit }) {
            axios.get(notes_api + '/notes').then(function(response) {
                commit("UPDATE_NOTES", response['data'])
            })
        },

        async ADD_NOTE({ commit }, note) {
            axios.post(notes_api + '/note/add', {
                name: note.name,
                desc: note.desc,
                favorites: note.favorites
            }).then(function(response) {
                commit("ADD_NOTE", response['data'])
            })
        },

        async EDIT_NOTE({ commit }, note) {
            axios.put(notes_api + '/note/update', {
                id: note.id,
                name: note.name,
                desc: note.desc
            }).then(function(response) {
                commit('UPDATE_NOTE', response['data'])
            })
        },

        async DELETE_NOTE({ commit }, id) {
            axios.delete(notes_api + '/note/delete/' + id).then(function() {
                commit('DELETE_NOTE', id)
            })
        },

        async ADD_TO_FAVOURITE({ commit }, id) {
            axios.put(notes_api + '/note/toFavourite', {
                id: id
            }).then(function(response) {
                commit('UPDATE_NOTE', response['data'])
            })
        },

        async FAVOURITE_FILTER({ commit }) {
            axios.get(notes_api + '/notes/filter/favorites').then(function(response) {
                commit("UPDATE_NOTES", response['data'])
            })
        },

        async DATE_FILTER({ commit }) {
            axios.get(notes_api + '/notes/filter/date').then(function(response) {
                commit("UPDATE_NOTES", response['data'])
            })
        }
    },
    mutations: {
        ADD_NOTE(state, note) {
            state.notes.push(note)
        },
        DELETE_NOTE(state, id) {
            let index = state.notes.findIndex(n => n.id == id);
            state.notes.splice(index, 1);
        },
        UPDATE_NOTE(state, note) {
            let index = state.notes.findIndex(n => n.id == note.id);
            state.notes[index] = note
        },
        UPDATE_NOTES(state, notes) {
            state.notes = notes
        }
    }
}