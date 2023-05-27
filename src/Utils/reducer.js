/* global rtsbParams */

import * as Types from "./actionType";

export const initialState = {
	saveType: null,
	options: {
		isLoading: true,
		selected_post_types: [],
	},
	generalData:{
		isLoading: true,
		postTypes: [],
		postTypesMeta: [
			{
				value: 'hello_main',
				label: 'hello_main',
			},
			{
				value: 'hello_main_2',
				label: 'hello_main_2',
			},
			{
				value: 'hello_main_3',
				label: 'hello_main_3',
			},
		],
		selectedMenu: localStorage.getItem("cptwi_current_menu") || 'settings',
	},
};

const reducer = (state, action) => {
	switch (action.type) {
		case Types.UPDATE_OPTIONS:
			return {
				...state,
				saveType: action.saveType,
				options: action.options,
			};
		case Types.GENERAL_DATA:
			return {
				...state,
				generalData : action.generalData,
			};
		default:
			return state;
	}
};

export default reducer;

