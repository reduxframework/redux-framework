/**
 * WordPress dependencies.
 */
const { __ } = wp.i18n;

const {
	Fragment,
	useEffect,
	useRef
} = wp.element;

const CSSEditor = ({
	                   attributes,
	                   setAttributes,
	                   clientId
                   }) => {
	useEffect( () => {
		let classes = getClassName();

		if ( attributes.customCSS ) {
			const generatedCSS = ( attributes.customCSS ).replace( /.ticss-[a-zA-Z0-9_-]*/g, 'selector' );
			customCSSRef.current = generatedCSS;
		} else {
			customCSSRef.current = 'selector {\n}\n';
		}

		editorRef.current = wp.CodeMirror( document.getElementById( 'redux-css-editor' ), {
			value: customCSSRef.current,
			autoCloseBrackets: true,
			continueComments: true,
			lineNumbers: true,
			lineWrapping: true,
			matchBrackets: true,
			lint: true,
			gutters: [ 'CodeMirror-lint-markers' ],
			styleActiveLine: true,
			styleActiveSelected: true,
			extraKeys: {
				'Ctrl-Space': 'autocomplete',
				'Alt-F': 'findPersistent',
				'Cmd-F': 'findPersistent'
			}
		});

		editorRef.current.on( 'change', () => {
			const regex = new RegExp( 'selector', 'g' );
			const generatedCSS = editorRef.current.getValue().replace( regex, `.${ classArRef.current }` );
			customCSSRef.current = generatedCSS;

			if ( ( 'selector {\n}\n' ).replace( /\s+/g, '' ) === customCSSRef.current.replace( /\s+/g, '' ) ) {
				return setAttributes({ customCSS: null });
			}

			setAttributes({ customCSS: customCSSRef.current });
		});
	}, []);

	useEffect( () => {
		let classes = getClassName();

		setAttributes({
			hasCustomCSS: true,
			className: classes
		});
	}, [ attributes ]);

	const getClassName = () => {
		let classes;

		const uniqueId = clientId.substr( 0, 8 );

		if ( null !== customCSSRef.current && ( 'selector {\n}\n' ).replace( /\s+/g, '' ) === customCSSRef.current.replace( /\s+/g, '' ) ) {
			return attributes.className;
		}

		if ( attributes.className ) {
			classes = attributes.className;

			if ( ! classes.includes( 'ticss-' ) ) {
				classes = classes.split( ' ' );
				classes.push( `ticss-${ uniqueId }` );
				classes = classes.join( ' ' );
			}

			classArRef.current = classes.split( ' ' );
			classArRef.current = classArRef.current.find( i => i.includes( 'ticss' ) );
		} else {
			classes = `ticss-${ uniqueId }`;
			classArRef.current = classes;
		}

		return classes;
	};

	const editorRef = useRef( null );
	const customCSSRef = useRef( null );
	const classArRef = useRef( null );

	return (
		<Fragment>
			<p>{ __( 'Add your custom CSS.' ) }</p>

			<div id="redux-css-editor" className="redux-css-editor"/>

			<p>{ __( 'Use' ) } <code>selector</code> { __( 'to target block wrapper.' ) }</p>
			<p>{ __( '' ) }</p>
			<p>{ __( 'Example:' ) }</p>

			<pre className="redux-css-editor-help">
				{ 'selector {\n    background: #000;\n}\n\nselector img {\n    border-radius: 100%;\n}'}
			</pre>

			<p>{ __( 'You can also use other CSS syntax here, such as media queries.' ) }</p>
		</Fragment>
	);
};

export default CSSEditor;
