const sequelize = require('../db')
const DataTypes = sequelize.Sequelize

const Category = sequelize.define('category', {
  id: {
    type: DataTypes.INTEGER(13),
    allowNull: false,
    primaryKey: true,
    autoIncrement: true
  },
  createdAt: {
    field: 'created',
    type: DataTypes.DATE,
    allowNull: false,
    defaultValue: sequelize.fn('current_timestamp')
  },
  updatedAt: {
    field: 'modified',
    type: DataTypes.DATE,
    allowNull: false,
    defaultValue: sequelize.fn('current_timestamp')
  },
  name: {
    type: DataTypes.STRING(255),
    allowNull: false,
    unique: true
  }
}, {
  tableName: 'category'
})

module.exports = Category
