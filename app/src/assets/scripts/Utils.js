import router from "@/router"
import localBase from "./LocalBase"

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

    checkUserAlreadyLogged: () => {
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
    },

    destroySession: () => {
        localBase.remove(localBase.keys.login.user);
        localBase.remove(localBase.keys.login.enteprise);

        return router.push({
            path: '/login'
        });
    }
}

export default Utils
