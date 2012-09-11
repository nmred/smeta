#include <stdio.h>
#include <stdlib.h>
#include <stdbool.h>
#include <string.h>

bool str_in(char **);

void str_sort(char *[], int);

void swap(char **p1, char **p2);
void str_out(char *[], int);

const size_t BUFFER_LEN = 256;
const size_t NUM_P = 3;

// {{{ main 

int main(void)
{
	char *pS[3] = {NULL};
	int count = 0;

	printf("\nEnter successive lines, pressing Enter at the end of each line.\nJust press Enter to end.\n");
	for(count = 0; count < NUM_P; count++) {
		if (!str_in(&pS[count])) {
			break;
		}
	}

	str_sort(pS, count);
	str_out(pS, count);

	return 0;
}

// }}}

// {{{ str_in

bool str_in(char **pString)
{
	char buffer[BUFFER_LEN];

	if (fgets(buffer, BUFFER_LEN, stdin) == NULL) {
		printf("\nError reading string.\n");
		exit(1);
	}

	if (buffer[0] == '\0') {
		return false;
	}

	*pString = (char *)malloc(strlen(buffer) + 1);

	if (*pString == NULL) {
		printf("\nOut of memory");
		exit(1);
	}

	strcpy(*pString, buffer);

	return true;
}


// }}}
// {{{ str_sort

void str_sort(char *p[], int n)
{
	char *pTemp = NULL;
	bool sorted = false;
	int i = 0;
	while(!sorted) {
		sorted = true;
		for(i = 0; i < n-1; i++) {
			if (strcmp(p[i], p[i + 1]) > 0) {
				sorted = false;
				swap(&p[i], &p[i + 1]);
			}
		}
	}
}

// }}}
//  {{{ swap

void swap(char **p1, char **p2)
{
	void *pt = *p1;
	*p1 = *p2;
	*p2 = pt;
}

//  }}}
// {{{ str_out

void str_out(char *p[], int n)
{
	int i = 0;
	printf("\nYour input sorted in order is :\n\n");
	for (i = 0; i < n; i++) {
		printf("%s\n", p[i]);
		free(p[i]);
		p[i] = NULL;
	}
	return;
}

// }}}
