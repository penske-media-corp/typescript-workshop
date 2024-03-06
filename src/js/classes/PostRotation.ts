import sanitizeHtml from 'sanitize-html';

/**
 * Responsible for Rotating through active sponsored posts.
 */
export default class PostRotation {
	posts = Array<Record<string, string>>;
	activePost: Record<string, string>;
	dataAttr = 'data-pmc-sponsored-posts' as const;
	storage = 'pmcSponsoredPostIndex' as const;

	/**
	 * Constructor.
	 *
	 * @param posts
	 */
	constructor(posts : Array<Record<string, string>>) {
		this.posts = posts;
		this.init();
	}

	/**
	 * Initialize if we have more than 1 active sponsored posts.'
	 *
	 * @returns {void}
	 */
	init(): void {
		if (this.setupActivePost()) {
			this.displayActivePost();
		}

		const placements = this.getPlacements();

		placements.forEach((placement) => {
			placement.classList.add('pmc-sponsored-posts-visible');
		});
	}

	/**
	 * Sets the active post based where in rotation viewer.
	 * Index for sponsored post is stored in localStorage.
	 *
	 * @returns {number}
	 */
	setupActivePost(): number {
		let index = this.getPostIndex();

		index = (0 <= index) ? index : -1;

		if (0 > index || (index + 1) >= this.posts.length) {
			index = 0;
		} else {
			index++;
		}

		this.setPostIndex(index);
		this.activePost = this.posts[index];

		return index;
	}

	/**
	 * Helper to get post index from localStorage.
	 *
	 * @returns {number}
	 */
	getPostIndex(): number {
		return parseInt(global.localStorage.getItem(this.storage), 10) || 0;
	}

	/**
	 * Helper to set post index and save to localStorage.
	 *
	 * @param index
	 * 
	 * @returns {void}
	 */
	setPostIndex(index: number): void {
		global.localStorage.setItem(this.storage, String(index));
	}

	/**
	 * Displays the active sponsored post.
	 * 
	 * @returns {void}
	 */
	displayActivePost(): void {
		const activePost = this.activePost,
			placements = this.getPlacements();

		for (let i = 0; i < placements.length; i++) {
			let placement = placements[i],
				context = placement.getAttribute(this.dataAttr);

			if ("undefined" !== activePost[context]) {
				placement.innerHTML = sanitizeHtml(activePost[context], {
					allowedTags: sanitizeHtml.defaults.allowedTags.concat([ 'img', 'noscript' ]),
					allowedAttributes: false
				})
			}
		}
	}

	/**
	 * Helper to get sponsored post placements from DOM.
	 *
	 * @returns {NodeListOf<Element>}
	 */
	getPlacements(): NodeListOf<Element> {
		return document.querySelectorAll('['+this.dataAttr+']');
	}
}
