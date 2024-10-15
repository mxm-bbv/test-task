export const register = async () => {
    const response = await fetch('https://api.live.local/api/v1/user/register', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        },
    });

    if (response.ok) {
        if (response.hasOwnProperty("message")) {
            console.log('Пользователь зарегистрирован:', response.data.message);
        }
        return response.data
    } else {
        console.error('Error saving file:', response.statusText);
    }
}