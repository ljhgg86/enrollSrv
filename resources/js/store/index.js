import Vue from 'vue'
import Vuex from 'vuex'
import 'babel-polyfill'

Vue.use(Vuex)

const state = {
    activityId:0,
    activityInfo:{}
};

const getters = {
};

const mutations = {
	setActivityId(state,data){
		state.activityId=data;
	},
	setActivity(state,data){
		state.activityInfo=data;
	},
};

const actions = {
};

export default new Vuex.Store({
  state,
  getters,
  mutations,
  actions,
})
