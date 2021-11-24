import * as types from './mutation-types'

export const addToCart = ({ commit ,state}) => {
	commit(types.ADD_TO_CART,{
		id: state.cart.added+1
	});
}

export const retrieveData = ({ commit }) => {
	commit(types.RETRIEVE_DATA,{
		id: 1
	});
}