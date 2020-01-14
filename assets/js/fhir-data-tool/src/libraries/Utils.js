import moment from 'moment'
import {date_format} from '@/variables'

const download = (filename, text) => {
    const element = document.createElement('a')
    element.setAttribute('href', 'data:text/plaincharset=utf-8,' + encodeURIComponent(text))
    element.setAttribute('download', filename)

    element.style.display = 'none'
    document.body.appendChild(element)

    element.click()

    document.body.removeChild(element)
}

const formatDate = (date) => {
  if(!date) return ''
  const date_string = moment(date).format(date_format) // date_format defined in variables
  return date_string
}

  export {download, formatDate}