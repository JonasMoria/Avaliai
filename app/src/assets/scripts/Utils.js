import router from "@/router"
import localBase from "./LocalBase"
import api from "@/services/api"

const Utils = {
    maskCpf: (cpf) => {
        if (!cpf) return ''

        let value = cpf.replace(/\D/g, '')

        value = value.slice(0, 11)

        value = value.replace(/(\d{3})(\d)/, '$1.$2')
        value = value.replace(/(\d{3})(\d)/, '$1.$2')
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2')

        return value
    },

    maskCnpj: (cnpj) => {
        if (!cnpj) return ''

        let value = cnpj.replace(/\D/g, '')

        value = value.slice(0, 14)

        value = value.replace(/^(\d{2})(\d)/, '$1.$2')
        value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
        value = value.replace(/\.(\d{3})(\d)/, '.$1/$2')
        value = value.replace(/(\d{4})(\d)/, '$1-$2')

        return value
    },

    maskPhone: (phone) => {
        if (!phone) return ''

        let value = phone.replace(/\D/g, '')
        value = value.slice(0, 11)

        if (value.length <= 10) {
            value = value.replace(/^(\d{2})(\d)/, '($1) $2')
            value = value.replace(/(\d{4})(\d)/, '$1-$2')
        } else {
            value = value.replace(/^(\d{2})(\d)/, '($1) $2')
            value = value.replace(/(\d{5})(\d)/, '$1-$2')
        }

        return value
    },

    maskCep: (cep) => {
        if (!cep) return ''

        let value = cep.replace(/\D/g, '')

        value = value.slice(0, 8)

        value = value.replace(/^(\d{5})(\d)/, '$1-$2')

        return value
    },

    filterWord: (word) => {
        return word
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/\s+/g, '')
            .replace(/[^a-zA-Z0-9]/g, '');
    },

    keepNumbersOnly: function (value) {
        return value.replace(/\D/g, '');
    },

    checkUserAlreadyLogged: () => {
        try {
            const userSession = JSON.parse(
                localBase.select(localBase.keys.login.user)
            );
            const enterpriseSession = JSON.parse(
                localBase.select(localBase.keys.login.enteprise)
            );

            if (userSession) {
                return router.push({
                    path: '/usuario/home'
                });
            }
            if (enterpriseSession) {
                return router.push({
                    path: '/empresa/home'
                });
            }
        } catch (error) {
            return;
        }
    },

    logoutApi: () => {
        try {
            const userSession = JSON.parse(
                localBase.select(localBase.keys.login.user)
            );
            const enterpriseSession = JSON.parse(
                localBase.select(localBase.keys.login.enteprise)
            );

            if (userSession) {
                api.post('/user/logout', {}, {
                    headers: {
                        Authorization: `Bearer ${userSession.token}`
                    }
                });
            }

            if (enterpriseSession) {
                api.post('/enterprise/logout', {}, {
                    headers: {
                        Authorization: `Bearer ${enterpriseSession.token}`
                    }
                });
            }
        } catch (error) {
            return;
        }
    },

    getEnterpriseSessionToken: function () {
        const enterpriseSession = JSON.parse(
            localBase.select(localBase.keys.login.enteprise)
        );

        if (enterpriseSession.token !== undefined && enterpriseSession.token) {
            return enterpriseSession.token;
        }

        return '';
    },

    getUserSessionToken: function () {
        const userSession = JSON.parse(
            localBase.select(localBase.keys.login.user)
        );

        if (userSession.token !== undefined && userSession.token) {
            return userSession.token;
        }

        return '';
    },

    destroySession: () => {
        localBase.remove(localBase.keys.login.user);
        localBase.remove(localBase.keys.login.enteprise);

        return router.push({
            path: '/login'
        });
    },
    destroySessionWithLoginRequired: () => {
        localBase.remove(localBase.keys.login.user);
        localBase.remove(localBase.keys.login.enteprise);

        return router.push({
            path: '/login?expired=true'
        });
    }
}

export default Utils
