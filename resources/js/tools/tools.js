export function convertResponse422(response) {
    let messages = [];
    Object.values(response).forEach(value => {
        messages = messages.concat(value);
    })

    return messages.join('\n');
}