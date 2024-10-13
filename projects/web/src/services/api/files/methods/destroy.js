export const destroy = async (file) => {
    const formData = new FormData();
    formData.append('file', file);

    const response = await fetch('https://api.live.local/api/v1/file/destroy', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
        },
        body: formData,
    });

    if (response.ok) {
        console.log('File saved');
    } else {
        console.error('Error saving file:', response.statusText);
    }
}