wp.blocks.registerBlockType( 'prince/professor-block', {
  title: 'New professor',
  icon: 'calendar',
  category: 'common',
  attributes: {
      title: {type: 'string'},
      content: {type: 'string'},
      date: {type: 'string'}
  },
  edit: function( props ) {

    function updateTitle( professor ) {
      props.setAttributes({ title: professor.target.value })
    }

    function updateContent( professor ) {
      props.setAttributes({ content: professor.target.value })
    }


      return React.createElement( "div", null, 
                React.createElement( "h3", null, "Create a new professor" ), 
                React.createElement( "input", {
                placeholder: "Professor Name",
                type: "text",
                value: props.attributes.title,
                onChange: updateTitle
              }), 
                React.createElement( "textarea", {
                placeholder: "Professor Bio",
                type: "text",
                value: props.attributes.content,
                onChange: updateContent
              })
          );
  },
  save: function( props ) {
      // return React.createElement("p", null, props.attributes.content);
      return null;
  }
} )