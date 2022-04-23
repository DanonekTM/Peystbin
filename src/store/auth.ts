import AuthService from '../services/authService';
import { Commit } from 'vuex';
import { getDecodedJWT, timestampToDateTimeConverter } from '@/utils/tools';

type authState = {
	user: string,
	userInfo: Record<string, unknown>
}

export const auth = {
	namespaced: true,
	state: {
		user: localStorage.getItem('AUTH_TOKEN') || undefined,
		userInfo: {}
	},
	getters: {
		isAuthenticated(state: authState) : boolean {
			return state.user !== undefined;
		},
		getAccessToken(state: { user: string; }) : string {
			return state.user ?? undefined;
		},
		getNickname: (state: { userInfo: { nickname: string; }; }) : string => state.userInfo.nickname,
		getUserEmail: (state: { userInfo: { email: string; }; }) : string => state.userInfo.email,
		getJoinDate: (state: { userInfo: { join_date: string; }; }) : string => state.userInfo.join_date,
		getTokenExpiry: (state: { userInfo: { tokenExpiry: number; }; }) : number => state.userInfo.tokenExpiry,
	},
	actions: {
		login({ commit } : { commit: Commit }, creds: Array<string>): Promise<authState> {
			return AuthService.login(creds).then(
				user => {
					commit('updateDetails', user);
					return Promise.resolve(user);
				},
				error => {
					return Promise.reject(error);
				}
			);
		},
		refreshToken({ commit } : { commit: Commit }): Promise<authState> {
			return AuthService.refreshToken().then(
				user => {
					commit('updateDetails', user);
					return Promise.resolve(user);
				},
				error => {
					return Promise.reject(error);
				}
			);
		},
		logout({ commit } : { commit: Commit }) : void {
			AuthService.logout();
			commit('logout');
		}
	},
	mutations: {
		updateDetails(state: authState, user: string) : void {
			state.user = user;
			state.userInfo.nickname = getDecodedJWT(user)['data']['user_nickname'];
			state.userInfo.email = getDecodedJWT(user)['data']['user_email'];
			state.userInfo.join_date = timestampToDateTimeConverter(getDecodedJWT(user)['data']['user_join_date'], false);
			state.userInfo.tokenExpiry = getDecodedJWT(user)['exp'];
		},
		logout(state: authState) : void {
			state.user = undefined;
			state.userInfo = {};
		}
	}
};