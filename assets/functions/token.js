export const createToken = count => {
    let token = '';
    for(let i=0 ; i < count ; i++) {
        token += Math.floor(Math.random() * 10);
    }
    return token;
}