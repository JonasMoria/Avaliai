/**
 * Store informations
 */

const localBase = (() => {

    const keys = {
        login : {
            user: 'avaliai_login_user',
            enteprise: 'avaliai_login_enterprise'
        }
    };

    function insert(key, data) {
        localStorage.setItem(key, data);
    }

    function select(key) {
        return localStorage.getItem(key);
    }

    function remove(key) {
        localStorage.removeItem(key);
    }

    function update(key, data) {
        localStorage.setItem(key, data);
    }

    function flushAll() {
        localStorage.clear();
    }

    return {
        keys,

        insert,
        select,
        remove,
        update,
        flushAll,
    }
})();

export default localBase;