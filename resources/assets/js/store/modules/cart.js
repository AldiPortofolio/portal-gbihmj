import * as types from '../mutation-types'

const state = {
  added: '',
  checkoutStatus: null
}

// mutations
const mutations = {
  [types.ADD_TO_CART] (state, { id }) {
    state.added 	= id;
  },
  [types.RETRIEVE_DATA] (state, { id }) {
    state.added 	= id;
  },

}

export default {
  state,
  mutations
}