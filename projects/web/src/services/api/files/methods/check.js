export const check = async (data) => {
    if (!(data instanceof File)) {
        const response = await fetch('https://api.live.local/api/v1/file/check', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
            }
        });

        if (response.ok) {
            const responseData = await response.json();
            return responseData.data ? responseData.data[0] : false;
        }

        return false;
    }

    const formData = new FormData();
    formData.append('file', data);

    const response = await fetch('https://api.live.local/api/v1/file/check', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
        },
        body: formData,
    });

    if (response.ok) {
        const responseData = await response.json();
        return responseData.data.exists;
    }

    return false;
}