import localBase from "./LocalBase"

export function isUserAuthenticated() {
   return !!localBase.select(localBase.keys.login.user);
}

export function isEnterpriseAuthenticated() {
   return !!localBase.select(localBase.keys.login.enteprise);
}
