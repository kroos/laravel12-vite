// Import all JS modules under modules folder
const modules = import.meta.glob('./modules/**/*.js');

// Map create/edit to form.js automatically
const ACTION_MAP = {
	create: 'form',
	edit: 'form'
};

/**
 * Load the JS module for a given Laravel route
 * @param {string} routeName - current route, e.g., "users.children.edit"
 */
export async function loadModule(routeName) {

	if (!routeName) return;

	try {

		const parts = routeName.split('.');
		const action = parts.pop(); // last segment
		const folderPath = parts.join('/');

		const fileName = ACTION_MAP[action] || action;

		const modulePath = folderPath
		? `./modules/${folderPath}/${fileName}.js`
		: `./modules/${fileName}.js`;

		const loader = modules[modulePath];

		if (!loader) {
			console.log(
				`[ModuleLoader] JS module missing for route "${routeName}".\n` +
				`Expected module at: ${modulePath}`
			);
			return; // fail-safe, do not throw
		}

		const module = await loader();

		if (module.default) {
			module.default({
				routeName,
				action,
				segments: parts
			});
		}

	} catch (error) {
		console.error('[ModuleLoader] Failed to load module for route', routeName, error);
	}
}
