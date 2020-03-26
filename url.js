const Url = {
    encode: (string) => {
        return string.replace(/[^A-z0-9]+/g, "+").toLowerCase()
    }
}

module.exports = Url
