#include <string>
#include <iterator>
#include <exception>
#include <iostream>
#include <limits>
/**
 *	@class Node
 *	A basic Linked-List node.
 */
template<typename T> 
class Node {
public:
	Node() { mp_link = 0; }
	Node<T> *mp_link;
	T data;
};

/**
 * @class LinkedListIterator
 */
template<typename T> class LinkedListIterator : public std::iterator<std::forward_iterator_tag,T> {
	typedef Node<T> iNode;
public:
	LinkedListIterator(iNode *link) {
		item=link;
	}
	LinkedListIterator<T>& operator++ () {
		if(!item) {
			throw new std::out_of_range("Iterating past end");
		}
		item = item->mp_link;
		return *this;
	}
	LinkedListIterator<T> operator++(int) {
		if(!item) {
			throw new std::out_of_range("Iterating past end");
		}
		LinkedListIterator<T> temp(item);
		operator++();
		return temp;
	}
	bool operator== (const LinkedListIterator<T> &other) {
		return item == other.item;
	}
	bool operator!= (const LinkedListIterator<T> &other) {
		return item != other.item;
	}
	iNode *data() const { return item; }
private:
	iNode *item;
};

/**
 *	@class LinkedList
 *	A basic Linked-List implemented as a stack (FILO).
 */
template<typename T>
class LinkedList {
	typedef Node<T> iNode;
	void init() {
		mp_head = mp_tail = 0;
		m_size = 0;
	}
public:
	 LinkedList<T>() {
		 init();
	 }
#ifdef CXX11
	/**
	 *	This is the C++11 initializer_list constructor.
	 *	<code>
		LinkedList<int> list = {1,2,3,4,5};
	 *	</code>
	 *	It seems VC10 and VC11 still don't support this.
	 */
    LinkedList(std::initializer_list<T> list){
		init();
        for( const T* iter=list.begin(); iter != list.end(); iter++) {
            push(*(iter));
        }
    }
#endif
	/**
	 *	Pushes a value onto the end of the list.
	 *	@return a reference to the list object, so that it can be chained:
	 *	<code>
	 	LinkedList<int> list;
		list.push(1).push(2);
	 	</code>
	 */
	LinkedList<T> &push(const T &val) {
		iNode *n = new iNode;
		n->data = val;
		if(mp_tail) {
			mp_tail->mp_link = n;
			mp_tail = n;
		} else {
			mp_head = mp_tail = n;
		}
		m_size++;
		return *this;
	}

	/**
	 *	Pops a value from the top.
	 */
	T pop() {
		if(!mp_head)
			return T();
		iNode *top = mp_head;
		T ret = top->data;
		mp_head = top->mp_link;
		delete top;
		m_size--;
		return ret;
	}

	/**
	 * returns the current value.
	 */
	T& current() const {
		if(!mp_head)
			return T();
		return mp_head->data();
	}
	size_t size() const {
		return m_size;
	}

	LinkedListIterator<T> begin() const {
		return LinkedListIterator<T>(mp_head);
	}
	LinkedListIterator<T> end() const {
		return LinkedListIterator<T>(0);
	}

	void reverse() {
		mp_tail = mp_head;
		mp_head = reverse(mp_head);
	}
	void reverseRecursive() {
		mp_tail = mp_head;
		mp_head = reverseRecursive(mp_head);
	}

private :
	iNode * reverse(iNode *current) {
		iNode *temp;
		iNode *previous = 0;

		/* shuffle around */
		while (current) {
			temp = current->mp_link;
			current->mp_link = previous;
			previous = current;
			current = temp;
		}
		return previous;
	}

	iNode* reverseRecursive(iNode *current, iNode *previous=0) {


		/* If we've recursed to the end of the linked list, start swapping pointers */
		if(!current->mp_link) {
			current->mp_link = previous;
			return current;
		} else {
			/* the point of recursion */
			iNode *t = reverseRecursive(current->mp_link,current);
			/* the pointer flip */
			current->mp_link = previous;
			return t;
		}
	}

	iNode *mp_head;
	iNode *mp_tail;
	size_t m_size;
};



typedef LinkedList<int> IntList;
typedef LinkedList<std::string> StringList;

int main(int argc, char **argv) {
	IntList ilist;
	ilist.push(1).push(2).push(4).push(8);
	
	std::cout << "Displaying the original list" << std::endl;
	for(LinkedListIterator<int> i = ilist.begin(); i!=ilist.end(); i++) {
		std::cout << i.data()->data << " ";
	}
	std::cout << std::endl << std::endl << "Performing a recursive reverse on the linked list..." << std::endl;
	ilist.reverseRecursive();

	for(LinkedListIterator<int> i = ilist.begin(); i!=ilist.end(); i++) {
		std::cout << i.data()->data << " ";
	}

	std::cout << std::endl << std::endl << "Performing an iterative reverse on the linked list..." << std::endl;
	ilist.reverse();

	for(LinkedListIterator<int> i = ilist.begin(); i!=ilist.end(); i++) {
		std::cout << i.data()->data << " ";
	}
	std::cout << std::endl << std::endl << "Press ENTER to continue...";
	std::cin.ignore( std::numeric_limits<std::streamsize>::max(), '\n' );

  return 0;
}