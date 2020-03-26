const Sequelize = require("sequelize")

const sequelize = new Sequelize("gifgrabber", "gifgrabber", "***REMOVED***", {
    dialect: "mysql"
})

module.exports = sequelize
