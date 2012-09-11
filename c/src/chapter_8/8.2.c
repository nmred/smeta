#include <stdio.h>

int main(void)
{
	int count = 0;
	do {
		int count = 0;
		++count;
		printf("\ncount = %d", count);	
	} while(++count <= 8);

	printf("\ncount = %d\n", count);

	return 0;
}
