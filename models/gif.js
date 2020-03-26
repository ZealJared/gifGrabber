const sequelize = require('../db')
const DataTypes = sequelize.Sequelize

const Gif = sequelize.define('gif', {
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
  approved: {
    type: DataTypes.INTEGER(4),
    allowNull: false,
    defaultValue: '0'
  },
  title: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  caption: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  url: {
    type: DataTypes.STRING(255),
    allowNull: true,
    unique: true
  },
  type: {
    type: DataTypes.STRING(4),
    allowNull: false
  },
  categoryId: {
    type: DataTypes.INTEGER(13),
    allowNull: false,
    references: {
      model: 'category',
      key: 'id'
    }
  }
}, {
  tableName: 'gif'
})

Gif.afterDestroy(gif => {
  const fs = require('fs')
  fs.unlink("./public/images/" + gif.id + "." + gif.type, err => {
    if(err){
      console.log(err)
    }
  })
})

module.exports = Gif
