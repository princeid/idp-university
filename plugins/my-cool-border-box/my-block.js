wp.blocks.registerBlockType('prince/border-box', {
    title: 'New Border Box',
    icon: 'smiley',
    category: 'common',
    attributes: {
        content: {type: 'string'},
        color: {type: 'string'}
    },
    edit: function(props) {
        function updateContent(event) {
            props.setAttributes({content: event.target.value})
        }

        function updateColor(value) {
            props.setAttributes({color: value.hex})
        }

        return /*#__PURE__*/ React.createElement("div", null, /*#__PURE__*/
        React.createElement("h3", null, "Your Cool Border Box"), /*#__PURE__*/
         React.createElement("input", {
          type: "text",
          value: props.attributes.content,
          onChange: updateContent
        }), /*#__PURE__*/React.createElement(wp.components.ColorPicker, {
          color: props.attributes.color,
          onChangeComplete: updateColor
        }));
    },
    save: function(props) {
        return /*#__PURE__*/ React.createElement("h3", {
          style: {
            border: `5px solid ${props.attributes.color}`
          }
        }, props.attributes.content);
    }
})