export default {
    checkperm(perms) {
        return axios.post('/api/v1/auth/checkperm', {
            perm: perms
        }).then(({data}) => {
            return data.hasperm;
        });
    }
}
