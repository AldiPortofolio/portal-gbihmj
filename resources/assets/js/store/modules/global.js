import * as types from '../mutation-types'

const state = {
  global: {},
  social_media: {},
  payment_methods: {},
  auth:''
}

//Getters
const getters   = {
  global: state => {
    return state.global;
  },
  social_media: state => {
    return state.social_media;
  },
  payment_methods: state => {
    return state.payment_methods;
  },
  auth:state => {
    return state.auth;
  }
}

//Actions
const actions   = {
  booting ({ commit },data){
    commit(types.BOOTING,{data});
  },
  auth({commit},data){
    commit(types.AUTH,{data});
  },
  logout({commit}){
    commit(types.LOGOUT);
  }
}

// mutations
const mutations = {
  [types.BOOTING] (state,res) {
    state.global          = res.data.global;
    state.social_media    = res.data.social_media;
    state.payment_methods = res.data.payment_methods;
  },[types.AUTH] (state,res) {
    if(res.data.user.status == 'success'){
      state.auth  = res.data.user.user;
    }
  },[types.LOGOUT] (state) {
    state.auth    = '';
  },
}

export default {
  state,
  mutations,
  actions,
  getters
}