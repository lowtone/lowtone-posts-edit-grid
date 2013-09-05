tinymce = @tinymce

numerators = [
		"one"
		"two"
		"three"
		"four"
		"five"
		"six"
		"seven"
		"eight"
		"nine"
		"ten"
		"eleven"
		"twelve"
		"thirteen"
		"fourteen"
		"fifteen"
		"sixteen"
	]

denominators = [
		"whole"
		"half"
		"third"
		"fourth"
		"fifth"
		"sixth"
		"seventh"
		"eighth"
		"ninth"
		"tenth"
		"eleventh"
		"twelfth"
		"thirteenth"
		"fourteenth"
		"fifteenth"
		"sixteenth"
	]

tinymce.create 'tinymce.plugins.LowtonePostsEditGrid',
	init: (d, e) -> 
	createControl: (d, e) ->
		ed = tinymce.activeEditor

		switch d
			when 'lowtone_posts_edit_grid_shortcodes'
				d = e.createMenuButton 'lowtone_posts_edit_grid_shortcodes',
					title: ed.getLang 'lowtone_posts_edit_grid.insert'
					icons: false

				a = @

				d.onRenderMenu.add (c, b) ->
					widths = []

					ucfirst = (str) ->
						str[0].toUpperCase() + str.substr(1).toLowerCase()

					for denominator, i in denominators
						target = b

						if ++i > 2
							target = b.addMenu 
								title: ed.getLang "lowtone_posts_edit_grid.#{denominator}s"

						for j in [1..i]
							width = j/i

							continue if widths.indexOf(width) > -1

							numerator = numerators[j-1]

							denominator = denominators[i-1]

							denominator += 's' if j > 1

							title = ucfirst "#{ed.getLang("lowtone_posts_edit_grid.#{numerator}")} #{ed.getLang("lowtone_posts_edit_grid.#{denominator}")}"

							key = "#{numerator}-#{denominator}"

							a.addImmediate target, title, "[#{key}][/#{key}]"

							widths.push width

					b.addSeparator()

					a.addImmediate b, ed.getLang('lowtone_posts_edit_grid.clear'), '[clear]'

				return d

		null
	addImmediate: (d, e, a) ->
		d.add
			title: e,
			onclick: ->
				tinyMCE.activeEditor.execCommand 'mceInsertContent', false, a


tinymce.PluginManager.add 'LowtonePostsEditGrid', tinymce.plugins.LowtonePostsEditGrid